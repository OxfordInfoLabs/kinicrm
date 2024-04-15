import {Component, OnInit} from '@angular/core';
import {environment} from '../../../environments/environment';
import {AuthenticationService} from 'ng-kiniauth';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.sass']
})
export class LoginComponent implements OnInit {

    public environment = environment;
    public loginRoute = '/';

    constructor(private authService: AuthenticationService) {
    }

    ngOnInit(): void {
        this.authService.getSessionData();

        const existingURL = localStorage.getItem('lastVisitedURL') || '/';
        this.loginRoute = sessionStorage.getItem('authGuardCapturedURL') || existingURL;
    }

    public loggedIn() {
        this.authService.getLoggedInUser();
        sessionStorage.removeItem('authGuardCapturedURL');
    }
}
