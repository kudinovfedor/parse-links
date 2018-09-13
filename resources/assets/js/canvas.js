((w, d) => {
    'use strict';

    //const html = d.documentElement, scrollBarWidth = w.innerWidth - html.clientWidth;
    const links = w.links;

    //console.log(links && links.length);

    const floor = number => Math.floor(number);
    const pow = (number, exponent) => Math.pow(number, exponent);
    const random = () => Math.random();
    const round = number => Math.round(number);
    const sqrt = number => Math.sqrt(number);

    const getRandomRGBColor = () => {
        const red = round(random() * 255);
        const green = round(random() * 255);
        const blue = round(random() * 255);

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
        gradient.addColorStop(0, getRandomRGBColor());
        gradient.addColorStop(1, getRandomRGBColor());

        return gradient;
    };

    const getDistance = (x1, y1, x2, y2) => {
        const xAxisDistance = x2 - x1;
        const yAxisDistance = y2 - y1;

        return sqrt(
            pow(xAxisDistance, 2) + pow(yAxisDistance, 2)
        );
    };

    const initCanvas = () => {

        const canvas = d.createElement('canvas'), ctx = canvas.getContext('2d');
        const diameter = 30, radius = diameter / 2;

        let width = w.innerWidth, height = w.innerHeight, particles;

        canvas.id = 'canvas';
        canvas.width = width;
        canvas.height = height;

        d.body.appendChild(canvas);

        const resizeCanvas = () => {
            width = canvas.width = w.innerWidth;
            height = canvas.height = w.innerHeight;

            drawCanvas();
        };

        w.addEventListener('resize', resizeCanvas);

        const cols = floor(width / diameter), rows = floor(height / diameter);

        const limit = cols * rows;

        //console.log(`Cols: ${cols}; Rows: ${rows}; Limit: ${limit};`);

        console.time('[Draw Canvas]');
        drawCanvas();
        console.timeEnd('[Draw Canvas]');

        function drawCanvas() {
            let x = 0, y = 0, color, particle;

            ctx.clearRect(0, 0, width, height);

            particles = [];

            links.forEach((value, index) => {

                /* Random location*/
                x = floor(random() * width);
                y = floor(random() * height);
                if (x < radius) x = radius;
                if (y < radius) y = radius;
                if ((width - x) < radius) x -= radius;
                if ((height - y) < radius) y -= radius;


                /* Location in columns and rows (part one) */
                /*
                                if (x < radius) x = radius;
                                if (y < radius) y = radius;*/

                color = getRandomRGBColor();
                //color = getRandomLinearGradient(ctx, x, y, radius);

                particle = new Particle(value.id, value.childs, x, y, radius, color);
                particle.draw(ctx);

                particles.push(particle);

                /* Location in columns and rows (part two) */
                /*
                                if (x + radius <= width) x += 15 + radius * 2;
                                if (x + radius > width) {
                                    x = radius;
                                    y += radius * 2 + 15;
                                }*/

            });

            for (let j = 0; j < particles.length; j++) {
                //particles.forEach(particle => {
                //console.log(particle);

                let x1, y1, x2, y2;

                let childs = particles[j].childs;

                //console.log(`Child: %o`, particles[j]);

                for (let i = 0; i < childs.length; i++) {
                    let child = getParticleById(childs[i], particles);

                    x1 = particles[j].x;
                    y1 = particles[j].y;

                    //console.log(getParticleById(childs[i], particles));
                    //console.log(childs[i]);

                    if (child && child.id !== particles[j].id) {

                        x2 = child.x;
                        y2 = child.y;

                        console.log(getAngleSlopeLine(x1, y1, x2, y2));
                        ctx.beginPath();
                        ctx.moveTo(x1, y1);
                        ctx.strokeStyle = particles[j].color;
                        //ctx.strokeStyle = child.color;
                        ctx.lineWidth = 1;
                        //ctx.setLineDash([5, 5]);
                        //ctx.lineDashOffset = 100;
                        ctx.lineTo(x2, y2);
                        drawArrow(ctx, x1, y1, x2, y2);
                        ctx.stroke();
                        ctx.closePath();
                    }

                }

                break;

            }

            //console.log(particles);
        }
    };

    const getParticleById = (id, particles) => {
        for (let i = 0; i < particles.length; i++) {
            if (particles[i].id !== id) {
                continue;
            }

            return particles[i];
        }

        return false;
    };

    const drawArrow = (context, x1, y1, x2, y2, length) => {
        const headLength = length || 8;	// length of head in pixels
        const dx = x2 - x1;
        const dy = y2 - y1;
        const angle = Math.atan2(dy, dx);

        context.moveTo(x2, y2);
        context.lineTo(x2 - headLength * Math.cos(angle - Math.PI / 6), y2 - headLength * Math.sin(angle - Math.PI / 6));
        context.moveTo(x2, y2);
        context.lineTo(x2 - headLength * Math.cos(angle + Math.PI / 6), y2 - headLength * Math.sin(angle + Math.PI / 6));
    };

    const getAngleSlopeLine = (x1, y1, x2, y2) => {
        const VR = y2 - y1;
        const GR = x2 - x1;
        const angularCoefficient = VR / GR;

        return {
            'atan': Math.atan(angularCoefficient) / Math.PI * 180,
            'atan2': Math.atan2(VR, GR) / Math.PI * 180,
            'atanh': Math.atanh(angularCoefficient) / Math.PI * 180,
        }
    };

    d.addEventListener('DOMContentLoaded', () => {

        if (links && links.length) {
            initCanvas();
        }

    });

    class Particle {
        constructor(id, childs, x, y, radius, color) {
            this.x = x;
            this.y = y;
            this.id = id;
            this.color = color;
            this.radius = radius;
            this.diameter = 2 * this.radius;
            this.childs = childs;
        }

        draw(ctx) {
            ctx.beginPath();

            ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
            ctx.fillStyle = this.color;
            ctx.fill();

            ctx.fillStyle = getContrastColor(this.color);
            //ctx.font = 'normal 10px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(this.id, this.x, this.y);

            ctx.closePath();
        }
    }

})(window, document);
