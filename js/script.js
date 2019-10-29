function init() {
    changeTab('home', document.getElementsByClassName('tab-selector')[0]);
}

function changeTab(tabId, tabSelector) {
    const $tab = document.getElementById(tabId);
    const tabs = document.getElementsByClassName('tab');
    for(const tab of tabs) {
        if(tab !== $tab) {
            tab.style.display = 'none';
        } else {
            tab.style.display = 'inherit';
        }
    }

    const tabSelectors = document.getElementsByClassName('tab-selector');
    for(const tabSect of tabSelectors) {
        if(tabSect !== tabSelector) tabSect.className = 'tab-selector tab-selector-unselected';
        else tabSect.className = 'tab-selector';
    }
}