import {Component, Input, OnInit} from '@angular/core';
import {CommentService} from '../../services/comment.service';
import {AuthenticationService} from 'ng-kiniauth';
import {GravatarService} from '../../services/gravatar.service';

@Component({
  selector: 'kcrm-comments',
  templateUrl: './comments.component.html',
  styleUrls: ['./comments.component.css']
})
export class CommentsComponent implements OnInit {

    @Input() scope: string = '';
    @Input() scopeId: number = 0;

    public comments: any = [];
    public loggedInUser: any = {};
    public loggedInGravatar: string = '';

    constructor(private commentService: CommentService,
                private authService: AuthenticationService,
                private gravatarService: GravatarService) {
    }

    async ngOnInit() {
        if (this.scopeId) {
            this.loadComments();
        }

        this.loggedInUser = this.authService.authUser.getValue();
        this.loggedInGravatar = await this.gravatarService.getGravatarURL(this.loggedInUser.emailAddress);
    }

    public async addComment(target: any, event?: any) {
        if (event) {
            const keyCode = event.which || event.keyCode;
            if (keyCode === 13 && !event.shiftKey) {
                event.preventDefault();

                this.sendComment(target);
            }
        } else {
            this.sendComment(target);
        }
    }

    private async sendComment(target: any) {
        if (target.value) {
            await this.commentService.createComment(this.scope, this.scopeId, target.value);
        }

        target.value = '';
        this.loadComments();
    }

    private async loadComments() {
        this.comments = await this.commentService.searchForComments(this.scope, this.scopeId).toPromise();
    }
}
