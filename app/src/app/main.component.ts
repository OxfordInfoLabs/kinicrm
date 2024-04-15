import {ChangeDetectorRef, Component, OnDestroy, OnInit} from '@angular/core';
import {AuthenticationService, NotificationService} from 'ng-kiniauth';
import {ProjectService, TagService} from 'ng-kinintel';
import {MatLegacyDialog as MatDialog} from '@angular/material/legacy-dialog';
import {ActivationEnd, Router} from '@angular/router';
import {MediaMatcher} from '@angular/cdk/layout';
import {SidenavService} from './services/sidenav.service';
import {environment} from '../environments/environment';
import {Subscription} from 'rxjs';

@Component({
    selector: 'app-root',
    template: '<router-outlet></router-outlet>'
})
export class MainComponent implements OnInit, OnDestroy{

    public loggedIn = false;

    private routerSub: Subscription;
    private authSub!: Subscription;

    constructor(private authService: AuthenticationService,
                private router: Router,
                private projectService: ProjectService) {

        this.routerSub = this.router.events.subscribe(e => {
            document.getElementById('main')?.scrollTo(0, 0);
        });
    }

    async ngOnInit() {
        await this.authService.getSessionData();

        this.authSub = this.authService.authUser.subscribe(async user => {
            this.loggedIn = user && user.id;
            if (this.loggedIn) {
                this.projectService.setActiveProject({projectKey: environment.projectKey});
            }
        });
    }

    ngOnDestroy() {
        this.authSub.unsubscribe();
    }
}
