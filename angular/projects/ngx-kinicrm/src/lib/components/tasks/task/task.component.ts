import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TaskService} from '../../../services/task.service';
import {MetadataService} from '../../../services/metadata.service';
import {GravatarService} from '../../../services/gravatar.service';
import moment from 'moment/moment';
import {Location} from '@angular/common';
import {MatSnackBar} from '@angular/material/snack-bar';
import _ from 'lodash';
import {UserService} from 'ng-kiniauth';
import {ContactService} from '../../../services/contact.service';
import {OrganisationService} from '../../../services/organisation.service';

@Component({
    selector: 'kcrm-task',
    templateUrl: './task.component.html',
    styleUrls: ['./task.component.css']
})
export class TaskComponent implements OnInit {

    public task: any;
    public statuses: any = [];
    public priorities: any = [];
    public users: any = [];
    public updateDate = false;
    public contact: any = null;
    public organisation: any = null;

    constructor(private route: ActivatedRoute,
                private taskService: TaskService,
                private metadataService: MetadataService,
                private gravatarService: GravatarService,
                private location: Location,
                private snackBar: MatSnackBar,
                private userService: UserService,
                private contactService: ContactService,
                private organisationService: OrganisationService) {
    }

    async ngOnInit() {
        this.route.params.subscribe(async (params: any) => {
            if (params.id) {
                this.task = await this.taskService.getTask(params.id);
                this.task.assignees.map(async (assignee: any) => {
                    if (assignee.emailAddress) {
                        assignee.photo = await this.gravatarService.getGravatarURL(assignee.emailAddress);
                    }
                    return assignee;
                });
                if (this.task.scopeObject && this.task.scopeObject.id) {
                    if (this.task.scopeObject.scope === 'Contact') {
                        this.contact = await this.contactService.getContact(this.task.scopeObject.id);
                    }
                    if (this.task.scopeObject.scope === 'Organisation') {
                        this.organisation = await this.organisationService.getOrganisation(this.task.scopeObject.id);
                    }
                }
            }
        });

        const users: any = await this.userService.getAdminUsers('', 1000, 0).toPromise();
        this.users = _.map(users.results || [], (user: any) => {
            return {
                id: user.id,
                name: user.name,
                emailAddress: user.emailAddress,
                status: user.status
            }
        });
        this.priorities = await this.metadataService.getReferenceTypes('Priority');
        this.statuses = await this.metadataService.getReferenceTypes('Status');
    }

    public back() {
        this.location.back();
    }

    public bindSelectValue(v1: any, v2: any) {
        if (v1 && v2 && v1.id === v2.id) {
            return v1.name;
        }
        return '';
    }

    public async saveTask(task: any, showSnack = true) {
        const clone = _.clone(task);
        if (clone.scopeObject) {
            delete clone.scopeObject;
        }
        await this.taskService.updateTask(clone);
        if (showSnack) {
            this.snackBar.open('Task Successfully Saved.', '', {
                duration: 2000,
                verticalPosition: 'top',
                panelClass: ['bg-gray-800', 'text-green-100', 'rounded-md', 'text-center']
            });
        }
    }

    protected readonly moment = moment;
}
