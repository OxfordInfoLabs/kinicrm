import {AfterViewInit, Component} from '@angular/core';

@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.sass']
})
export class HomeComponent implements AfterViewInit {

    public load = false;

    constructor() {
    }

    ngAfterViewInit() {
        setTimeout(() => {
            this.load = true;
        }, 0);
    }

}
