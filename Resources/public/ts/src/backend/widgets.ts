import triggerJqueryEvent from './jqueryevents';

let bindWidgets = function(element: HTMLElement)
{
    // Enabled select2.
    let select2Elements = element.querySelectorAll('select[data-widget="select2"]:not(.select2-hidden-accessible)');
    select2Elements.forEach((select2Element) => {
        $(select2Element).select2({
            theme: 'bootstrap'
        });
    });

    // Enable CKEditor.
    let ckEditorElements = element.querySelectorAll('.ckeditor-custom');
    ckEditorElements.forEach((select2Element: HTMLTextAreaElement) => {
        let textAreaInstanceName = select2Element.id;
        if ((window as any).CKEDITOR.instances[textAreaInstanceName]) {
            // CKEditor is already running.
            let cke = select2Element.parentElement.querySelector('.cke');
            if (null === cke) {
                // CKEditor instance is old, destroy and replace.
                (window as any).CKEDITOR.remove(textAreaInstanceName);
                (window as any).CKEDITOR.replace(select2Element, JSON.parse(select2Element.dataset.config));
            }
        } else {
            (window as any).CKEDITOR.replace(select2Element, JSON.parse(select2Element.dataset.config));
        }
    });

    // Bind file chooser.
    let fileSelectButtons = element.querySelectorAll('.btn-file-select');
    fileSelectButtons.forEach((fileSelectButton: HTMLElement) => {
        fileSelectButton.addEventListener('click', (event) => {
            event.preventDefault();
            // Add css classes.
            fileSelectButtons.forEach((btn: HTMLElement) => {
                btn.classList.remove('text-success');
            });
            fileSelectButton.classList.add('text-success');

            let uuid = fileSelectButton.dataset.uuid;
            let version = fileSelectButton.dataset.version;
            let title = fileSelectButton.dataset.title;

            // Todo: Replace.
            $(fileSelectButton).parentsUntil('.tab-content').parent().find('input.existing-file-uuid').val(uuid);
            $(fileSelectButton).parentsUntil('.tab-content').parent().find('input.existing-file-version').val(version);
            $(fileSelectButton).parentsUntil('form').parent().find('input.file-title').val(title);
        });
    });

    // Bind create buttons.
    let createButtons = element.querySelectorAll('.btn-create');
    createButtons.forEach((createButton: HTMLElement) => {
        createButton.addEventListener('click', (event) => {
            event.preventDefault();
            let createElementEvent = new CustomEvent('createElement', {
                detail: {
                    parent: createButton.dataset.uuid,
                    elementName: createButton.dataset.elementName,
                }
            });
            document.dispatchEvent(createElementEvent);
        });
    });

    // Trigger bindWidgets events for custom widgets.
    let event = new CustomEvent('bindWidgets', {
        detail: {
            element: element
        }
    });
    document.dispatchEvent(event);
    // Trigger jQuery fallback events for backwards compatibility.
    triggerJqueryEvent('bindWidgets', $(element), 'body');
};

export default bindWidgets;
