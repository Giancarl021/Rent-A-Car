function init() {
    // changeTab('home', document.getElementById('home-selector'));
    changeTab('clients', document.getElementsByClassName('tab-selector')[1]);
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
    }, _repaintTable);
}

function databaseInsert(table, row) {
    ajax('php/ajax/add.php', {
        table: table,
        row: row
    }, _repaintTable);
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

function formatTable(tableId) {

}

function addRow(tableType) {
    let modalContent;
    switch (tableType) {
        case 'clients':
            modalContent = '<h1 data-table="clients">Adicionar Cliente</h1>' +
                '<label for="__MODAL_CPF">CPF*</label>' +
                '<input data-key="cpf" id="__MODAL_CPF" type="text" required/>' +
                '<label for="__MODAL_NAME">NOME*</label>' +
                '<input data-key="name" id="__MODAL_NAME" type="text" required/>' +
                '<label for="__MODAL_ADDRESS">ENDEREÇO*</label>' +
                '<input data-key="address" id="__MODAL_ADDRESS" type="text" required/>' +
                '<label for="__MODAL_TELEPHONE">TELEFONE*</label>' +
                '<input data-key="telephone" id="__MODAL_TELEPHONE" type="text" required/>' +
                '<label for="__MODAL_DEBT">DÍVIDA</label>' +
                '<input data-key="debt" id="__MODAL_DEBT" type="text"/>' +
                '<button type="button" onclick="_getModalData(databaseInsert)">Cadastrar</button>' +
                '<button type="button" onclick="_closeModal()">Cancelar</button>';
            break;
        case 'cars':
            break;
        case 'rents':
            break;
    }
    _callModal(modalContent);
}

/* PRIVATE FUNCTIONS */

function _repaintTable(response) {
    const data = JSON.parse(response);
    if (data.error !== null) {
        _createToast(data.error);
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
        return `${r.join('')}<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td><td><button class='table-button delete-button' type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>`;
    });
    const header = $table.getElementsByTagName('tr')[0].outerHTML;
    $table.innerHTML = header + items.map(e => `<tr>${e}</tr>`).join('');
}

function _createToast(message) {
    alert(message)
}

function _callModal(content) {
    const $modal = document.getElementById('modal');
    if (content) {
        $modal.innerHTML = content;
    }
    $modal.style.pointerEvents = 'all';
    $modal.style.opacity = '1';

}

function _closeModal(persistContent = false) {
    const $modal = document.getElementById('modal');
    $modal.style.pointerEvents = 'none';
    $modal.style.opacity = '0';
    if (!persistContent) {
        setTimeout(() => {
            $modal.innerHTML = '';
        }, 300);
    }
}

function _getModalData(callback) {
    const modal = document.getElementById('modal');
    const inputs = modal.getElementsByTagName('input');
    const table = modal.getElementsByTagName('h1')[0].getAttribute('data-table');
    const row = {};

    for (input of inputs) {
        const val = input.value;
        if (!val) {
            _createToast('Preencha todos os campos de cadastro obrigatórios');
            return;
        }
        row[input.getAttribute('data-key')] = val;
    }
    if (callback) callback(table, row);
    return {table: table, row: row};
}