const intervals = {};

function init() {
    changeTab('home', document.getElementById('home-selector'));
    // changeTab('clients', document.getElementsByClassName('tab-selector')[1]);
    getClientsDebt();
    const tables = document.getElementsByTagName('table');
    for (const table of tables) {
        formatTable(table);
    }
}

/* AJAX */

function ajax(url, data = {}, callback) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            callback(this.responseText);
        }
    };

    request.open('POST', `${url}?data=${JSON.stringify(data)}`, true);
    request.send();
}

function filter(table, condition, elementId) {
    ajax('php/ajax/filter.php', {
        table: table,
        condition: condition,
        elementId: elementId
    }, repaintTable);
}

function updateData(response) {
    const data = JSON.parse(response);
    if (data.error !== null) {
        createToast(data.error);
        return false;
    }
    filter(
        data.elementId.substr(3),
        parseInt(
            document.getElementById(data.elementId)
                .parentElement
                .getElementsByClassName('button-selected')[0]
                .getAttribute('onclick')
                .split(',')[1]
        ),
        data.elementId
    );
    return true;
}

function databaseInsert(table, row) {
    ajax('php/ajax/add.php', {
            table: table,
            row: row
        },
        data => {
            if (updateData(data)) closeModal();
        }
    );
}

function databaseDelete(data) {
    const pk = data.origin.parentElement.parentElement.getElementsByTagName('td')[0].innerText.replace(/[^0-9a-zA-Z]/g, '');
    ajax('php/ajax/remove.php', {
        table: data.table,
        pk: pk
    }, updateData);
}

function databaseUpdate(data) {

}
/* COSMETIC */

function changeTab(tabId, tabSelector) {
    const $tab = document.getElementById(tabId);
    const tabs = document.getElementsByClassName('tab');
    for (const tab of tabs) {
        if (tab !== $tab) {
            tab.style.display = 'none';
        } else {
            tab.style.display = 'inherit';
        }
    }

    const $header = document.getElementsByTagName('header')[0];

    if (tabSelector.id !== 'home-selector') {
        $header.style.backgroundColor = 'rgb(46, 28, 58)';
    } else {
        $header.style.backgroundColor = 'transparent';
    }

    const tabSelectors = document.getElementsByClassName('tab-selector');
    for (const tabSect of tabSelectors) {
        if (tabSect !== tabSelector) tabSect.className = 'tab-selector tab-selector-unselected';
        else tabSect.className = 'tab-selector';
    }
}

function selectButton(element) {
    const $buttons = element.parentElement.getElementsByClassName('filter-selector');
    for (const $button of $buttons) {
        if ($button !== element) $button.className = 'filter-selector';
        else $button.className = 'filter-selector button-selected';
    }
}

function addRow(tableType) {
    let modalContent, callbacks = [], data = [];
    switch (tableType) {
        case 'clients':
            modalContent = '<h1 data-table="client">Adicionar Cliente</h1>' +
                '<label for="__MODAL_CPF">CPF*</label>' +
                '<input name="cpf" id="__MODAL_CPF" type="text" maxlength="14" onkeydown="modalMask(this, \'cpf\', event)" onchange="modalMask(this, \'cpf\')" required/>' +
                '<label for="__MODAL_NAME">NOME*</label>' +
                '<input name="name" id="__MODAL_NAME" type="text" maxlength="50" required/>' +
                '<label for="__MODAL_ADDRESS">ENDEREÇO*</label>' +
                '<input name="address" id="__MODAL_ADDRESS" type="text" maxlength="150" required/>' +
                '<label for="__MODAL_TELEPHONE">TELEFONE*</label>' +
                '<input name="telephone" id="__MODAL_TELEPHONE" type="text" maxlength="13" onkeydown="modalMask(this, \'telephone\', event)" onchange="modalMask(this, \'telephone\')" required/>' +
                '<label for="__MODAL_DEBT">DÍVIDA</label>' +
                '<input name="debt" id="__MODAL_DEBT" type="number" min="0" onchange="this.value = parseFloat(this.value).toFixed(2)"/>' +
                '<button type="button" class="window-confirm-button" onclick="getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="closeModal()">Cancelar</button>';
            break;
        case 'cars':
            modalContent = '<h1 data-table="car">Adicionar Carro</h1>' +
                '<label for="__MODAL_CARPLATE">PLACA*</label>' +
                '<input name="carPlate" type="text" id="__MODAL_CARPLATE" maxlength="7" onkeydown="modalMask(this, \'carPlate\', event)" onchange="modalMask(this, \'carPlate\')" required/>' +
                '<label for="__MODAL_CARYEAR">ANO*</label>' +
                '<input name="carYear" type="number" id="__MODAL_CARYEAR" min="1900" max="' + (new Date().getFullYear() + 1) + '" required/>' + // Máscara !!!
                '<label for="__MODAL_MODEL">MODELO*</label>' +
                '<input name="model" type="text"  id="__MODAL_MODEL" maxlength="20" required/>' +
                '<label for="__MODAL_DESCRIPTION">DESCRIÇÃO*</label>' +
                '<input name="description" id="__MODAL_DESCRIPTION" type="text"  maxlength="240" required/>' +
                '<label for="__MODAL_KM">Quilometragem</label>' +
                '<input name="km" id="__MODAL_KM" type="number" min="0" required/>' +
                '<label for="__MODAL_KMPRICE">PREÇO POR QUILÔMETRO*</label>' +
                '<input name="kmPrice" id="__MODAL_KMPRICE" type="number" min="0" onchange="this.value = parseFloat(this.value).toFixed(2)" required/>' +
                '<label for="__MODAL_DAILYTAX">TAXA DIÁRIA*</label>' +
                '<input name="dailyTax" id="__MODAL_DAILYTAX" type="number" min="0" onchange="this.value = parseFloat(this.value).toFixed(2)" required/>' +
                '<label for="__MODAL_OBSERVATIONS">OBSERVAÇÕES</label>' +
                '<input name="observations" id="__MODAL_OBSERVATIONS" type="text"  maxlength="240" required/>' +
                '<button type="button" class="window-confirm-button" onclick="getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="closeModal()">Cancelar</button>';
            break;
        case 'rents':
            modalContent = '<h1 data-table="rent">Adicionar Carro</h1>' +
                '<label for="__MODAL_CLIENTFK">CLIENTES*</label>' +
                '<select id="__MODAL_CLIENTFK" name="clientCpf" required><option value="null" hidden selected>Carregando...</option></select>' +
                '<label for="__MODAL_CARFK">CARROS*</label>' +
                '<select id="__MODAL_CARFK" name="carPlate" required><option value="null" hidden selected>Carregando...</option></select>' +
                '<label for="__MODAL_INITDATE">DATA DO ALUGUEL*</label>' +
                '<input name="initDate" type="datetime-local" step="1" id="__MODAL_INITDATE" required/>' +
                '<button class="datetime-button" onclick="toggleAutoDate(this, \'__MODAL_INITDATE\')" type="button">AGORA</button>' +
                '<label for="__MODAL_DEVOLUTIONDATE">DATA DE DEVOLUÇÃO</label>' +
                '<input name="devolutionDate" type="datetime-local" step="1" id="__MODAL_DEVOLUTIONDATE"/>' +
                '<button class="datetime-button" onclick="toggleAutoDate(this, \'__MODAL_DEVOLUTIONDATE\')" type="button">AGORA</button>' +
                '<button type="button" class="window-confirm-button" onclick="getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="closeModal()">Cancelar</button>';
            callbacks.push(getAvailableClients);
            callbacks.push(getAvailableCars);
            data.push('__MODAL_CLIENTFK');
            data.push('__MODAL_CARFK');
            break;
    }
    callModal(modalContent, callbacks, data);
}

function getClientsDebt() {
    ajax('php/ajax/getClientsDebt.php', {}, response => {
        const data = JSON.parse(response);
        if (data.error !== null) {
            createToast(data.error);
            return;
        }
        const span = document.getElementById('clientsDebt');
        span.innerHTML = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data.response);
    });
}

function repaintTable(response) {
    const data = JSON.parse(response);
    if (data.error !== null) {
        createToast(data.error);
        return;
    }

    const $table = document.getElementById(data.elementId);
    const complement = data.elementId === 'tb-rent' ? ['<td><button class=\'table-button return-button\' type=\'button\'><img src=\'img/return.svg\' alt=\'Return\'/></button></td>', '<td><button class=\'table-button return-button disabled-button\' type=\'button\'><img src=\'img/return.svg\' alt=\'Return\'/></button></td>'] : ['', ''];
    const items = data.result.map(e => {
        const r = [];
        let i = 0;
        while (e.hasOwnProperty(i)) {
            r.push(`<td>${e[i] === null ? 0 : e[i]}</td>`);
            i++;
        }
        return `${r.join('')}<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td><td><button type='button' class='table-button delete-button' onclick="callConfirmWindow(\'Deseja excluir esta linha? Esta ação NÃO poderá ser desfeita!\', databaseDelete, {table: \'${data.elementId.substr(3)}\', origin: this})" type='button'><img src='img/remove.svg' alt='Edit'/></button></td>${e[4] !== '0000-00-00 00:00:00' ? complement[0] : complement[1]}</tr>`;
    });
    const header = $table.getElementsByTagName('tr')[0].outerHTML;
    $table.innerHTML = header + items.map(e => `<tr>${e}</tr>`).join('');
    formatTable($table);
    if (data.elementId === 'tb-client') {
        getClientsDebt();
    }
}

function formatTable(table) {

    const cpfFormatter = function (data) {
        const arr = data.split('');
        arr[3] = '.' + arr[3];
        arr[6] = '.' + arr[6];
        arr[9] = '-' + arr[9];
        return arr.join('');
    };

    const dateFormatter = function (data) {
        if (!data || data === '0000-00-00 00:00:00') {
            return '-';
        }
        return new Intl.DateTimeFormat('pt-BR').format(new Date(data));
    };

    const moneyFormatter = function (data) {
        if (!data) {
            data = 0;
        }
        return new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data);
    };

    switch (table.id) {
        case 'tb-client':
            formatColumn(table, 0, cpfFormatter);
            formatColumn(table, 3, data => {
                const arr = data.split('');
                arr[0] = '(' + arr[0];
                arr[2] = ')' + arr[2];
                arr[arr.length - 4] = '-' + arr[arr.length - 4];
                return arr.join('');
            });
            formatColumn(table, 4, moneyFormatter);
            break;
        case 'tb-car':
            formatColumn(table, 4, data => {

                const arr = data.split('').reverse();
                if (arr.length < 4) return data;
                for (let i = 3; i < arr.length; i += 3) {
                    arr[i] += '.';
                }
                return arr.reverse().join('');
            });
            formatColumn(table, 5, moneyFormatter);
            formatColumn(table, 6, moneyFormatter);
            formatColumn(table, 7, data => {
                if (data === '0' || !data) {
                    return '-';
                }
                return data;
            });
            break;
        case 'tb-rent':
            formatColumn(table, 1, cpfFormatter);
            formatColumn(table, 3, dateFormatter);
            formatColumn(table, 4, dateFormatter);
            break;
        default:
            return;
    }

    function formatColumn(table, columnNumber, callback) {
        const lines = table.getElementsByTagName('tr');
        for (const line of lines) {
            const cell = line.getElementsByTagName('td')[columnNumber];
            if (!cell) continue;
            cell.innerText = callback(cell.innerText);
        }
    }
}

function createToast(message) {
    if (!message) return;
    const toast = document.getElementById('toast');
    if (toast.style.opacity === '1') return;
    toast.innerText = message;
    toast.style.opacity = '1';
    toast.style.pointerEvents = 'all';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.pointerEvents = 'none';
        setTimeout(() => toast.innerText = '', 300);
    }, 2000);
}

function callModal(content, callbacks = [], data = []) {
    const $modal = document.getElementById('modal');
    if (content) {
        $modal.innerHTML = content;
    }
    $modal.style.pointerEvents = 'all';
    $modal.style.opacity = '1';
    for (let i = 0; i < callbacks.length; i++) {
        callbacks[i](data[i]);
    }
}

function closeModal(persistContent = false) {
    const $modal = document.getElementById('modal');
    $modal.style.pointerEvents = 'none';
    $modal.style.opacity = '0';
    if (!persistContent) {
        setTimeout(() => {
            $modal.innerHTML = '';
        }, 300);
    }
}

function callConfirmWindow(message, callback = closeConfirmWindow, data = {}) {
    if (!message) return;
    const confirm = document.getElementById('confirm');
    if (confirm.style.opacity === '1') return;
    const confirmText = confirm.getElementsByTagName('h1')[0];
    const confirmButton = confirm.getElementsByClassName('window-confirm-button')[0];
    confirmButton.addEventListener('click', clickHandler);
    confirmText.innerText = message;
    confirm.style.pointerEvents = 'all';
    confirm.style.opacity = '1';

    function clickHandler() {
        confirmButton.removeEventListener('click', clickHandler);
        callback(data);
        closeConfirmWindow();
    }
}

function closeConfirmWindow() {
    const confirm = document.getElementById('confirm');
    const confirmText = confirm.getElementsByTagName('h1')[0];
    confirm.style.opacity = '0';
    confirm.style.pointerEvents = 'none';
    setTimeout(() => {
        confirmText.innerText = '';
    }, 300);
}

/* DATA MANIPULATION */

function getModalData(callback) {
    const modal = document.getElementById('modal');
    const inputs = modal.querySelectorAll('input,select');
    const table = modal.getElementsByTagName('h1')[0].getAttribute('data-table');
    const row = {};

    for (const input of inputs) {
        let val = input.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        if(input.type === 'datetime-local') {
            val = val.replace(/[TZ]/g, ' ').trim();
        }
        if (!val && input.hasAttribute('required')) {
            createToast('Preencha todos os campos de cadastro obrigatórios');
            return;
        }
        row[input.getAttribute('name')] = !val ? null : val;
    }
    if (callback) callback(table, row);
    return {table: table, row: row};
}

function getAvailableCars(elementId) {
    ajax('php/ajax/filter.php', {
        table: 'car',
        condition: 2,
        elementId: null
    }, response => {
        const data = JSON.parse(response);
        const element = document.getElementById(elementId);
        if (data.error !== null) {
            createToast(data.error);
            return;
        }
        const options = data.result.map(e => {
            return `<option value="${e[0]}">${e[0]} - ${e[2]}</option>`;
        });
        element.innerHTML = '<option selected hidden>Selecione o Carro</option>' + options;
    });
}

function getAvailableClients(elementId) {
    ajax('php/ajax/filter.php', {
        table: 'client',
        condition: 2,
        elementId: null
    }, response => {
        const data = JSON.parse(response);
        const element = document.getElementById(elementId);
        if (data.error !== null) {
            createToast(data.error);
            return;
        }
        const options = data.result.map(e => {
            return `<option value="${e[0]}">${e[1]}</option>`;
        });
        element.innerHTML = '<option selected hidden>Selecione o Cliente</option>' + options;
    });
}

function modalMask(element, pattern, event = null) {
    let value = element.value.trim().replace(/\s\s+/g, '');
    switch (pattern) {
        case 'cpf':
            if (event === null) {
                for (let i = 0; i < value.length; i++) {
                    if (value.charAt(i) !== '.' && (i === 3 || i === 7)) {
                        value = value.substring(0, i) + '.' + value.substring(i);
                    } else if (value.charAt(i) !== '-' && i === 11) {
                        value = value.substring(0, i) + '-' + value.substring(i);
                    }
                }
                if (value.length > 14) {
                    value = value.substr(0, 14);
                }
            } else {
                if (event.key === 'Backspace') {
                    const penultimateChar = value.charAt(value.length - 2);
                    if (penultimateChar === '.' || penultimateChar === '-') {
                        value = value.substr(0, value.length - 1);
                    }
                } else {
                    switch (value.length) {
                        case 3:
                        case 7:
                            value += '.';
                            break;
                        case 11:
                            value += '-';
                    }
                }
            }
            break;
        case 'telephone':
            if (event === null) {
                for (let i = 0; i < value.length; i++) {
                    if (value.charAt(i) !== '(' && i === 0) {
                        value = value.substring(0, i) + '(' + value.substring(i);
                    } else if (value.charAt(i) !== ')' && i === 3) {
                        value = value.substring(0, i) + ')' + value.substring(i);
                    }
                }
                if (value.length > 13) {
                    value = value.substr(0, 13);
                }
            } else {
                if (event.key === 'Backspace') {
                    const penultimateChar = value.charAt(value.length - 2);
                    if (penultimateChar === '(' || penultimateChar === ')') {
                        value = value.substr(0, value.length - 1);
                    }
                } else {
                    switch (value.length) {
                        case 0:
                            value += '(';
                            break;
                        case 3:
                            value += ')';
                            break;
                    }
                }
            }
            break;
        case 'carPlate':
            if (event === null) {
                value = value.normalize('NFD').replace(/[\u0300-\u036f]/, '').toUpperCase();
            } else {
                if (event.key.length === 1) {
                    event.preventDefault();
                    if (/^\w$/.test(event.key)) {
                        value += event.key.toUpperCase();
                    }
                }
                if (value.length > 7) {
                    value = value.substr(0, 7);
                }
            }
    }
    element.value = value;
}

function toggleAutoDate(origin, elementId) {
    const element = document.getElementById(elementId);

    if (element.hasAttribute('readonly')) {
        clearInterval(intervals[elementId]);
        element.removeAttribute('readonly');
        origin.className = origin.className.replace('datetime-button-selected', '');
        element.style.opacity = '.7s';
    } else {
        element.setAttribute('readonly', 'true');
        intervals[elementId] = setInterval(() => {
            element.value = getDateValue();
        }, 10);
        origin.className += ' datetime-button-selected';

        function getDateValue() {
            const date = new Date();
            return `${date.getFullYear()}-${formatNumber(date.getMonth() + 1)}-${formatNumber(date.getDate())}T${formatNumber(date.getHours())}:${formatNumber(date.getMinutes())}:${formatNumber(date.getSeconds())}`;

            function formatNumber(n) {
                return n < 10 ? '0' + n : n;
            }
        }
    }
}