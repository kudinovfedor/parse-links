((w, d) => {
    'use strict';

    const html = d.documentElement, scrollBarWidth = w.innerWidth - html.clientWidth;
    const links = w.links;

    console.log(links && links.length);

    const random = () => Math.random();
    const round = value => Math.round(value);
    const floor = value => Math.floor(value);

    const getRandomColor = () => {
        const red = round(random() * 255), green = round(random() * 255), blue = round(random() * 255);

        return `rgb(${red}, ${green}, ${blue})`;
    };

    const getContrastColor = color => {
        const rgb = color.slice(4, -1);
        const red = parseInt(rgb.split(',')[0], 10);
        const green = parseInt(rgb.split(',')[1], 10);
        const blue = parseInt(rgb.split(',')[2], 10);

        const contrast = (round(red * 299) + round(green * 587) + round(blue * 114)) / 1000;

        return (contrast >= 128) ? 'black' : 'white';
    };

    const getRandomLinearGradient = (ctx, x, y, r) => {
        const gradient = ctx.createLinearGradient(x, y - r, x, y + r);
        gradient.addColorStop(0, getRandomColor());
        gradient.addColorStop(1, getRandomColor());

        return gradient;
    };

    const initCanvas = () => {
        const canvas = d.createElement('canvas'), ctx = canvas.getContext('2d'), PI = Math.PI;

        let width = w.innerWidth, height = w.innerHeight;

        canvas.id = 'canvas';
        canvas.width = width;
        canvas.height = height;

        d.body.appendChild(canvas);

        const diameter = 30, r = diameter / 2, limit = floor(width / diameter) * floor(height / diameter);

        console.time('[Draw Canvas]');
        drawCanvas();
        console.timeEnd('[Draw Canvas]');

        function drawCanvas() {
            let x = 0, y = 0, rgb;

            ctx.clearRect(0, 0, width, height);

            links.forEach((value, index) => {
                //x = floor(random() * width);
                //y = floor(random() * height);

                if (x < r) x = r;
                if (y < r) y = r;
                //if ((width - x) < r) x -= r;
                //if ((height - y) < r) y -= r;

                rgb = getRandomColor();

                ctx.beginPath();

                ctx.arc(x, y, r, 0, 2 * PI);
                ctx.fillStyle = rgb;
                //ctx.fillStyle = getRandomLinearGradient(ctx, x, y, r);
                ctx.fill();

                ctx.fillStyle = getContrastColor(rgb);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(value.id, x, y);

                ctx.closePath();

                if (x + r <= width) x += r * 2;

                if (x + r > width) {
                    x = r;
                    y += r * 2;
                }
            });
        }

        const resizeCanvas = () => {
            width = canvas.width = w.innerWidth;
            height = canvas.height = w.innerHeight;

            drawCanvas();
        };

        w.addEventListener('resize', resizeCanvas);
    };

    d.addEventListener('DOMContentLoaded', () => {

        if (links && links.length) {
            initCanvas();
        }

    });

})(window, document);
