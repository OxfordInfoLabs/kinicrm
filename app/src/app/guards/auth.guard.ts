import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot, UrlTree} from '@angular/router';
import {Observable} from 'rxjs';
import {AuthenticationService} from 'ng-kiniauth';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {

    constructor(private router: Router,
                private authService: AuthenticationService) {
    }

    canActivate(
        route: ActivatedRouteSnapshot,
        state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {

        return this.authService.getLoggedInUser().then((user: any) => {
            if (user) {
                return true;
            }
            return this.router.navigate(['/login']);
        }).catch((error: any) => {
            return this.router.navigate(['/login']);
        });
    }

}
