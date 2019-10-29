class HomeCanvas {
    constructor(canvas) {
        this._canvas = canvas;
        this._canvas.onresize = this._render();
        this._context = this._canvas.getContext('2d');
    }

    _drawBg() {
        const ctx = this._context;
        const grd = ctx.createLinearGradient(0, 300, 150, 300);
        // const grd = ctx.createLinearGradient(0, 0, 100, 100);
        grd.addColorStop(0, 'green');
        grd.addColorStop(1, 'red');
        ctx.fillStyle = grd;
        ctx.fillRect(0, 0, 300, 300);
    }

    _getSize() {
        return {
            height: this._canvas.scrollHeight,
            width: this._canvas.scrollWidth
        }
    }

    _render() {

    }

}