document.addEventListener('DOMContentLoaded', function() {
    // Typewriter animation (existing code)
    const lines = [
        "Enter the World of Nuanu",
        "A space where your name becomes part of the journey."
    ];
    const elements = [
        document.getElementById('animated-title'),
        document.getElementById('animated-desc')
    ];
    let line = 0;
    function typeLine() {
        let i = 0;
        elements[line].textContent = '';
        function typeWriter() {
            if (i < lines[line].length) {
                elements[line].textContent += lines[line].charAt(i);
                i++;
                setTimeout(typeWriter, 60);
            } else {
                elements[line].style.borderRight = 'none';
                line++;
                if (line < lines.length) {
                    setTimeout(typeLine, 400);
                }
            }
        }
        typeWriter();
    }
    typeLine();

    // Button enable/disable logic
    const input = document.querySelector('input[name="fullname"]');
    const btn = document.querySelector('.btn[type="submit"]');
    function checkInput() {
        if (input.value.trim() === '') {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    }
    checkInput();
    input.addEventListener('input', checkInput);
});
