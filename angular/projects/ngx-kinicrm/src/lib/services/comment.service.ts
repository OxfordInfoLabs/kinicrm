import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import moment from 'moment';

@Injectable({
    providedIn: 'root'
})
export class CommentService {

    private moment = moment;

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }

    public createComment(scope: string, scopeId: number, message: string) {
        return this.http.post(this.config.adminHttpURL + '/crm/comment/' + scope + '/' + scopeId, JSON.stringify(message))
            .toPromise();
    }

    public searchForComments(scope: string, scopeId: number, searchString: string = '', limit = 10, offset = 0) {
        return this.http.get(this.config.adminHttpURL + '/crm/comment/' + scope + '/' + scopeId, {
            params: {searchString, limit: limit.toString(), offset: offset.toString()}
        }).pipe(map(async (comments: any) => {
                return await Promise.all(comments.map(async (comment: any) => {
                    let hash = '';
                    try {
                        const textAsBuffer = new TextEncoder().encode((comment.user.emailAddress).toLowerCase());
                        const hashBuffer = await window.crypto.subtle.digest('SHA-256', textAsBuffer);
                        const hashArray = Array.from(new Uint8Array(hashBuffer));
                        hash = hashArray
                            .map((item) => item.toString(16).padStart(2, '0'))
                            .join('');
                    } catch (e) {
                    }

                    comment.gravatarURL = 'https://gravatar.com/avatar/' + hash + '?d=mp&s=512';

                    comment.displayDate = this.moment(comment.createdDate).calendar();

                    return comment;
                }));
            })
        );
    }
}
