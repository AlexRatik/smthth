import {SOURCES_OF_TRUTH} from './utils/constants.mjs';

function createSourceOfTruthSelect() {
    const wrapper = document.createElement('section');
    wrapper.className = "mx-auto mt-3 w-50";

    const label = document.createElement('label');
    label.className = "form-label";
    label.htmlFor = "source-of-truth";

    wrapper.appendChild(label);

    const inputGroup = document.createElement('div');
    inputGroup.className = "input-group input-group-lg";

    const iconWrapper = document.createElement('span');
    iconWrapper.className = "input-group-text";

    const icon = document.createElement('i');
    icon.className = "bi bi-file-earmark-binary-fill";

    iconWrapper.appendChild(icon);

    inputGroup.appendChild(iconWrapper);

    const select = document.createElement('select');
    select.className = "form-select";
    select.id = 'source-of-truth';

    for (let i = 0; i < SOURCES_OF_TRUTH.length; i++) {
        const option = document.createElement('option');
        option.value = SOURCES_OF_TRUTH[i].value;
        option.textContent = SOURCES_OF_TRUTH[i].key;
        select.appendChild(option);
    }

    select.value = localStorage.getItem('source-of-truth') || SOURCES_OF_TRUTH[0].value;

    inputGroup.appendChild(select);

    wrapper.appendChild(inputGroup);

    document.body.prepend(wrapper);
}

createSourceOfTruthSelect();