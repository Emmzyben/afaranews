function animateColors() {
    var container = document.getElementById("container");
    var color1 = document.getElementById("color1");
    var color2 = document.getElementById("color2");
    var angle = 0;

    function updateColors() {
        angle += 1; // Increment angle
        var x = Math.cos(angle * Math.PI / 180) * 150; // Calculate new position
        var y = Math.sin(angle * Math.PI / 180) * 150;

        color1.style.left = (150 - x) + "px";
        color1.style.top = (150 - y) + "px";

        color2.style.left = (150 + x) + "px";
        color2.style.top = (150 + y) + "px";
    }

    // Call updateColors every 20 milliseconds
    setInterval(updateColors, 20);
}

animateColors();