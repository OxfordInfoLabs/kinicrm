import {ChangeDetectorRef, Component, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {AuthenticationService, UserService, NotificationService} from 'ng-kiniauth';
import {ProjectPickerComponent, ProjectService, TagService} from 'ng-kinintel';
import {MatLegacyDialog as MatDialog} from '@angular/material/legacy-dialog';
import {ActivatedRoute, ActivationEnd, Router} from '@angular/router';
import {MediaMatcher} from '@angular/cdk/layout';
import {environment} from '../../environments/environment';
import {MatSidenav} from '@angular/material/sidenav';
import {Subscription} from 'rxjs';
import * as _ from 'lodash';
import {SidenavService} from '../services/sidenav.service';

@Component({
    selector: 'app-map',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.sass']
})
export class AppComponent implements OnInit, OnDestroy {
    @ViewChild('snav', {static: false}) public snav!: MatSidenav;

    public mobileQuery: MediaQueryList;
    public environment = environment;
    public loggedIn = false;
    public sessionUser: any = {};
    public isLoading: boolean = true;
    public isAdmin = false;
    public session: any = {};
    public breadcrumbs: any = [];
    public activeSubmission: string;
    public brands: any = [];

    private readonly mobileQueryListener: () => void;
    private authSub!: Subscription;
    private user: any = {};
    private loadingSub!: Subscription;
    private routerSub: Subscription;

    constructor(private changeDetectorRef: ChangeDetectorRef,
                private media: MediaMatcher,
                private sidenavService: SidenavService,
                private dialog: MatDialog,
                private tagService: TagService,
                private authService: AuthenticationService,
                private router: Router,
                private notificationService: NotificationService,
                private projectService: ProjectService,
                private route: ActivatedRoute,
                private userService: UserService) {

        this.mobileQuery = media.matchMedia('(max-width: 768px)');
        this.mobileQueryListener = () => changeDetectorRef.detectChanges();
        this.mobileQuery.addListener(this.mobileQueryListener);

        this.routerSub = this.router.events.subscribe(e => {
            document.getElementById('main')?.scrollTo(0, 0);

            const snapshot: any = e instanceof ActivationEnd ? e.snapshot : null;
            if (snapshot && snapshot._routerState.url !== '/login') {
                localStorage.setItem('lastVisitedURL', snapshot._routerState.url);
            }

            this.breadcrumbs = [];
            const breadcrumbs: any = {};
            let url = '';
            let currentRoute: any = this.route.root;
            do {
                const childrenRoutes = currentRoute.children;
                currentRoute = null;
                childrenRoutes.forEach((childRoute: any) => {
                    if (childRoute.outlet === 'primary') {
                        const routeSnapshot = childRoute.snapshot;
                        url += '/' + routeSnapshot.url.map((segment: any) => segment.path).join('/');
                        if (childRoute.snapshot.data['title']) {
                            breadcrumbs[childRoute.snapshot.data['title']] = {
                                label: childRoute.snapshot.data['title'],
                                url: url.replace('//', '/')
                            };
                        }
                        currentRoute = childRoute;
                    }
                });
            } while (currentRoute);
            this.breadcrumbs = _.values(breadcrumbs);
        });
    }

    async ngOnInit() {
        this.authService.getSessionData();

        this.authSub = this.authService.authUser.subscribe(async user => {
            this.loggedIn = user && user.id;
            if (this.loggedIn) {
                this.user = user;
                this.session = this.authService.sessionData.getValue();
            }
        });
        this.loadingSub = this.authService.loadingRequests.subscribe(isLoading =>
            setTimeout(() => this.isLoading = isLoading, 0)
        );
    }

    ngOnDestroy() {
        this.mobileQuery.removeListener(this.mobileQueryListener);
        this.authSub.unsubscribe();
        this.loadingSub.unsubscribe();
    }

    public logout() {
        this.authService.logout().then(() => {
            this.router.navigate(['/login']);
        });
    }
}
