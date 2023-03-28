import {SOURCES_OF_TRUTH} from "./utils/constants.mjs";

const sourceOfTruthSelect = document.getElementById('source-of-truth');

sourceOfTruthSelect.addEventListener('change', changeSourceOfTruth);

function changeSourceOfTruth(e) {
    const value = e.target.value;
    localStorage.setItem('source-of-truth', value);
    document.cookie = `dataSource=${value}`;
    location.reload();
}