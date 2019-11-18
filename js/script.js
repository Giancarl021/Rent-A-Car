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
    let modalContent;
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
                '<label for="__MODAL_CARPLATE">Placa</label>' +
                '<input name="carPlate" type="text" id="__MODAL_CARPLATE" required/>' +
                '<label for="__MODAL_CARYEAR">Ano</label>' +
                '<input name="carYear" type="text" id="__MODAL_CARYEAR" required/>' +
                '<label for="__MODAL_MODEL" >Modelo</label>' +
                '<input name="model" type="text" required/>' +
                '<button type="button" class="window-confirm-button" onclick="getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="closeModal()">Cancelar</button>';
            break;
        case 'rents':
            modalContent = '<h1 data-table="rent">Adicionar Carro</h1>' +
                '<button type="button" class="window-confirm-button" onclick="getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="closeModal()">Cancelar</button>';
            break;
    }
    callModal(modalContent);
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
    const items = data.result.map(e => {
        const r = [];
        let i = 0;
        while (e.hasOwnProperty(i)) {
            r.push(`<td>${e[i] === null ? 0 : e[i]}</td>`);
            i++;
        }
        return `${r.join('')}<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td><td><button class='table-button delete-button' onclick="callConfirmWindow(\'Deseja excluir esta linha? Esta ação NÃO poderá ser desfeita!\', databaseDelete, {table: \'${data.elementId.substr(3)}\', origin: this})" type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>`;
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
            formatColumn(table, 4, data => {
                if (!data) {
                    data = 0;
                }
                return new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data);
            });
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
            formatColumn(table, 5, data => {
                if (data.includes('.')) {
                    const z = data.split('.')[1].split('').length;
                    return data.replace(/\./, ',') + (z === 1 ? '0' : '');
                }
                return data + ',00';
            });
            formatColumn(table, 6, data => {
                if (!data) {
                    data = 0;
                }
                return new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data);
            });
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

function callModal(content) {
    const $modal = document.getElementById('modal');
    if (content) {
        $modal.innerHTML = content;
    }
    $modal.style.pointerEvents = 'all';
    $modal.style.opacity = '1';

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
    const inputs = modal.getElementsByTagName('input');
    const table = modal.getElementsByTagName('h1')[0].getAttribute('data-table');
    const row = {};

    for (const input of inputs) {
        const val = input.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        if (!val && input.hasAttribute('required')) {
            createToast('Preencha todos os campos de cadastro obrigatórios');
            return;
        }
        row[input.getAttribute('name')] = !val ? null : val;
    }
    if (callback) callback(table, row);
    return {table: table, row: row};
}

function getAvaliableCars() {

}

function getAvaliableClients() {

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
        default:
            return;
    }
    element.value = value;
}