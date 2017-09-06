<script>
    let Easing = {
        easing: {
            linear: timeFraction => timeFraction,

            easeInElastic: (timeFraction, {amplitude = 1, frequency = 3, decay = 8} = {}) =>
                amplitude * Math.cos(frequency * timeFraction * 2 * Math.PI) / Math.exp(decay * (1 - timeFraction)),

            easeOutElastic: (timeFraction, {amplitude = 1, frequency = 3, decay = 8}) =>
                1 - Easing.easeInElastic(timeFraction, {amplitude, frequency, decay}),

            easeOutBounce: (timeFraction) => {
                return  timeFraction < 1   / 2.75 ? 7.5625 *  timeFraction                  * timeFraction :
                    timeFraction < 2   / 2.75 ? 7.5625 * (timeFraction -= 1.5   / 2.75) * timeFraction + .75 :
                        timeFraction < 2.5 / 2.75 ? 7.5625 * (timeFraction -= 2.25  / 2.75) * timeFraction + .9375 :
                            7.5625 * (timeFraction -= 2.625 / 2.75) * timeFraction + .984375;
            },
            easeOutExpo: (timeFraction) => {
                // return 1 - !~~timeFraction * 2**(-10 * timeFraction);
                return 1 - !~~timeFraction * Math.pow(2, (-10 * timeFraction));
            },
        },

        easing2d: {
            bezier: (timeFraction, points) => {
                if (points.length < 2) return points[0];
                let P = [];
                for (let i = 0; i < points.length - 1; ++i) {
                    P[i] = [points[i][0] + (points[i + 1][0] - points[i][0]) * timeFraction,
                        points[i][1] + (points[i + 1][1] - points[i][1]) * timeFraction];
                }
                return Animation.easing2d.bezier(timeFraction, P);
            },
            quadBezier0: (timeFraction, points) => {
                return [(1 - points[0]) * timeFraction, (1 - points[1]) * timeFraction];
            },
            quadBezier1: (timeFraction, points) => {
                return [(points[0] - 1) * timeFraction, (points[1] - 1) * timeFraction];
            },
            cubicBezier: (timeFraction, points) => {
                // return bezier(f, [[0, 0], [p[0], p[1]], [p[2], p[3]], [1, 1]])
                return Animation.easing2d.bezier(timeFraction, [[0, 0], ...points, [1, 1]]);
            },
            easeIn: (timeFraction) => {
                return Animation.easing2d.quadBezier0(timeFraction, [.42, 0])
            },
        }
    };

    export default {
        render(h) {
            return h(this.tag, [
                this.$scopedSlots.default({
                    value: this.motionValue,
                    to: this.to,
                    translateY: this.translateY,
                })
            ]);
        },

        props: {
            tag: {
                type: String,
                default: 'div'
            },

            value: {
                type: Number | String,
                default: 0
            },

            duration: {
                type: Number,
                default: 300
            },

            precision: {
                type: Number,
                default: 2
            },

            easing: {
                type: Function,
                default: (timeFraction) => {
                    return  timeFraction < 1   / 2.75 ? 7.5625 *  timeFraction                  * timeFraction :
                        timeFraction < 2   / 2.75 ? 7.5625 * (timeFraction -= 1.5   / 2.75) * timeFraction + .75 :
                            timeFraction < 2.5 / 2.75 ? 7.5625 * (timeFraction -= 2.25  / 2.75) * timeFraction + .9375 :
                                7.5625 * (timeFraction -= 2.625 / 2.75) * timeFraction + .984375;
                }, //timeFraction => timeFraction,
            },

            reverse: {
                type: Boolean,
                default: false,
            },

            params: {
                type: Object
            },

            begin: {
                type: Function,
                default: () => {}
            },

            process: {
                type: Function,
                default() {}
            },

            complete: {
                type: Function,
                default: () => {}
            },
        },

        watch: {
            value(newValue, oldValue) {
                if (this.value !== this.endValue) {
                    if (this.reverse) {
                        this.startValue = newValue;
                        this.endValue = oldValue;
                    } else {
                        this.startValue = oldValue;
                        this.endValue = newValue;
                    }

                    this.play();
                }
            }
        },

        data() {
            return {
                factor: Math.pow(10, this.precision),
                offsetIndex: 0,
                startValue: 0,
                motionValue: this.value,
                endValue: 0,
                paused: false,
                animationId: null,
                startTime: null,
                timePassed: null,
                progress: 0,
            }
        },

        methods: {
            play({reverse = false} = {}) {
                this.paused = false;
                this.animationId = requestAnimationFrame((time) => {
                    // this.duration += this.startTime - time;
                    this.startTime = time;
                    this.begin(time);
                    this.nextFrame(time);
                });
            },

            to(element, index) {
                if (typeof element === 'function') {
                    element = element();
                }

                this.offsetIndex = index;
                const elementRect = element.getBoundingClientRect(),
                    distanceX = elementRect.width, // TODO: add margin
                    distanceY = elementRect.height + 4; // TODO: add margin

                if (this.reverse) {
                    this.startValue = distanceY;
                    this.endValue = this.value;
                    element.style.position = 'absolute';
                } else {
                    this.startValue = this.value;
                    this.endValue = distanceY;
                }

                this.play();
            },

            translateY(value, index) {
                value = value != null ? value : this.startValue;

                return (this.motionValue && (index >= this.offsetIndex)) ? `translateY(${this.motionValue}px)` : '';
            },

            stop() {
                cancelAnimationFrame(this.animationId);
                this.motionValue = this.startValue;
            },

            nextFrame(time) {
                this.timePassed = time - this.startTime;
                if (this.timePassed < this.duration) {
                    this.animationId = requestAnimationFrame(this.nextFrame)
                } else {
                    this.timePassed = this.duration;

//                    this.motionValue = this.startValue;

                }

                let timeFraction = this.timePassed / this.duration,
                    deformation = this.easing(timeFraction, this.params);

                this.motionValue = Math.round((
                    this.startValue + (this.endValue - this.startValue) * deformation
                ) * this.factor) / this.factor;

                this.progress = timeFraction * 100;
                this.process(timeFraction, deformation);

                if (timeFraction === 1) {
                    this.$emit('complete', time);
                    this.complete(time);
//                    debugger;
//                        this.$nextTick(() => {
//                            this.motionValue = this.startValue;
//                        });
                }
            },
        },
    }
</script>
