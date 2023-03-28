export function createCheckbox(labelText, className, id, name = '') {
    const checkboxContainer = document.createElement('div');
    checkboxContainer.className = "checkbox " + className;

    const checkbox = document.createElement('input');
    checkbox.id = `checkbox-${id}`;
    checkbox.name = name;
    checkbox.type = "checkbox";

    const label = document.createElement('label');
    label.setAttribute('for', `checkbox-${id}`);
    label.textContent = labelText;

    checkboxContainer.appendChild(checkbox);
    checkboxContainer.appendChild(label);

    return checkboxContainer;
}