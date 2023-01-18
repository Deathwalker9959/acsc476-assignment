const convertFileToBase64Sync = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            resolve(reader.result);
        };
        reader.onerror = function (error) {
            reject(error);
        };
    });
}

const createPopup = async (attributes) => {
    let html = "";
    let title = "Add Item";
    let confirmButtonText = undefined;
    let confirmButtonColor = undefined
    let showCancelButton = undefined;
    let cancelButtonText = undefined;
    let cancelButtonColor
    for (const attr of attributes) {
        switch (attr.type) {
            case "title":
                title = attr.placeholder;
                break;
            case "text":
                html += `<input id="${attr.id}" type="${attr.type}" class="swal2-input" placeholder="${attr.placeholder}" value="${attr.defaultValue ? attr.defaultValue : ""}" ${attr.hidden ? "hidden" : ""}>`;
                break;
            case "number":
                html += `<input id="${attr.id}" type="${attr.type}" class="swal2-input" placeholder="${attr.placeholder}" value="${attr.defaultValue ? attr.defaultValue : ""}" ${attr.hidden ? "hidden" : ""}>`;
                break;
            case "select":
                html += `<select id="${attr.id}" class="swal2-input swal2-select" placeholder="${attr.placeholder}" ${attr.multiple ? 'multiple="multiple"' : ""
                    }>`
                if (attr.options.length > 0) {
                    for (const option of attr.options) {
                        html += `
                                <option ${option.value ? `value="${option.value}"` : ""} ${option.disabled ? 'disabled' : ''} ${option.selected ? 'selected' : ''}>${option.name}</option>
                            `;
                    }
                } else {
                    html += `<option value="none" disabled>No options</option>`
                }
                html += "</select>";
                break;
            case "label":
                html += `<h4 class="swal2-label mt-2 mb-0">${attr.text}</h4>`;
                break;
            case "file":
                html += `<input id="${attr.id}" type="file" class="swal2-file form-control" placeholder="${attr.placeholder}" ${attr.multiple ? 'multiple' : ''}>`;
                break;
            case "image":
                if (attr.src)
                    html += `<img id="${attr.id}" src="${attr.src}" class="swal2-image" ${attr.alt ? `alt="${attr.alt}"` : ""} ${attr.width ? `width="${attr.width}"` : ""} ${attr.height ? `height="${attr.height}"` : ""}>`;
                break;
            case "confirmButtonText":
                confirmButtonText ??= attr.text;
                confirmButtonColor ??= attr.color;
                break;
            case "showCancelButton":
                showCancelButton = true;
                cancelButtonText ??= attr.text;
                cancelButtonColor ??= attr.color;
                break;
            default:
                break;
        }
    }

    const { value: inputs } = await new swal({
        title: title,
        ...(confirmButtonText) && {confirmButtonText: confirmButtonText},
        ...(confirmButtonColor) && {confirmButtonColor: confirmButtonColor},
        ...(cancelButtonColor) && {cancelButtonColor: cancelButtonColor},
        ...(cancelButtonText) && {cancelButtonText: cancelButtonText},
        ...(showCancelButton) && {showCancelButton: showCancelButton},
        html: html,
        preConfirm: function () {
            return new Promise(async function (resolve) {

                const valid = attributes.every(attribute => !attribute.required ||
                    (attribute.hasOwnProperty('required') &&
                        (attribute.type === 'file' ? document.getElementById(attribute.id).files.length > 0 :
                            attribute.type === 'text' || attribute.type === 'number' || attribute.type === 'select' ? document.getElementById(attribute.id).value.length > 0 :
                                attribute.type === 'checkbox' ? document.getElementById(attribute.id).checked :
                                    false)
                    ));


                if (!valid) {
                    swal.showValidationMessage(`Please enter required fields`)
                }

                const inputs = {};
                for (const attr of attributes) {
                    switch (attr.type) {
                        case "text":
                        case "number":
                            inputs[attr.id] = document.getElementById(`${attr.id}`).value;
                            break;
                        case "select":
                            inputs[attr.id] = $(`#${attr.id} :selected`).map(function (i, el) {
                                return $(el).val();
                            }).get();
                            break;
                        case "file":
                            const files = document.getElementById(`${attr.id}`).files;
                            inputs[attr.id] = [];
                            for (let i = 0; i < files.length; i++) {
                                inputs[attr.id].push(await convertFileToBase64Sync(files[i]));
                            }
                            break;
                        default:
                            break;
                    }
                }
                resolve(inputs);
            });
        },
        didOpen: function () {
            document.getElementById(attributes.find(attr => attr.id).id).focus();
        },
    });

    return JSON.stringify(inputs);
};

export {
    createPopup
}