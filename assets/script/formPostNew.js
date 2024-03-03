console.log("Hello from formPostNew !")

const formContentElement = document.querySelector('#post_content');
const characterCountElement = document.querySelector('#character_count');

console.log(formContentElement)


const updateCharacterCount = () => {
    console.log("slt")
    const characterCount = formContentElement.value.length;
    characterCountElement.textContent = `${characterCount} characters (20 to 4096 characters required)`;

    if (characterCount >= 20 && characterCount <= 4096) {
        characterCountElement.style.color = 'green';
    } else {
        characterCountElement.style.color = 'red';
    }
};

formContentElement.addEventListener('input', updateCharacterCount);
document.addEventListener('DOMContentLoaded', updateCharacterCount);