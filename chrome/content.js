// Define the fillForm function to target and fill all input elements on the page
function fillForm() {
    // Select all input fields and textareas
    const inputs = document.querySelectorAll('input, textarea, select');

    inputs.forEach((input) => {
        if (input.type === 'text') {
            input.value = Math.random().toString(36).substring(7); //random text
        } else if (input.type === 'email') {
            input.value = 'sample@example.com';
        } else if (input.type === 'number') {
            input.value = Math.floor(Math.random() * 100); // Random number
        } else if (input.type === 'checkbox') {
            input.checked = true; // Check all checkboxes
        } else if (input.type === 'radio') {
            const radioGroup = document.querySelectorAll(`input[name="${input.name}"]`);
            if (radioGroup.length) {
                // Check the first radio button in the group
                radioGroup[0].checked = true;
            }
        } else if (input.tagName.toLowerCase() === 'select') {
            if (input.options.length > 0) {
                // Select the first option in dropdowns
                input.selectedIndex = 0;
            }
        }
    });
}

// Set up the button click listener to execute the fillForm function
document.getElementById("fillFormButton").addEventListener("click", () => {
    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
        chrome.scripting.executeScript({
            target: { tabId: tabs[0].id },
            function: fillForm
        });
    });
});