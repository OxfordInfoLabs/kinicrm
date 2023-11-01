import {Component, OnInit} from '@angular/core';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {Router} from '@angular/router';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import {ContactService} from '../../services/contact.service';

@Component({
  selector: 'kcrm-contacts',
  templateUrl: './contacts.component.html',
  styleUrls: ['./contacts.component.css']
})
export class ContactsComponent implements OnInit {

    public contacts: any = [];
    public searchText = new BehaviorSubject('');
    public limit = 10;
    public offset = 0;
    public page = 1;
    public endOfResults = false;
    public loading = true;

    private reload = new Subject();

    constructor(private router: Router,
                private contactService: ContactService) {
    }

    ngOnInit(){
        merge(this.searchText, this.reload)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.getContacts()
                )
            ).subscribe((contacts: any) => {
            this.endOfResults = contacts.length < this.limit;
            this.contacts = contacts;
            this.loading = false;
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });
    }

    public editContact(contact?: any) {
        const id = contact ? contact.id : 0;
        this.router.navigate(['/crm', 'contacts', id]);
    }

    public increaseOffset() {
        this.page = this.page + 1;
        this.offset = (this.limit * this.page) - this.limit;
        this.reload.next(Date.now());
    }

    public decreaseOffset() {
        this.page = this.page <= 1 ? 1 : this.page - 1;
        this.offset = (this.limit * this.page) - this.limit;
        this.reload.next(Date.now());
    }

    public pageSizeChange(value: number) {
        this.limit = value;
        this.reload.next(Date.now());
    }

    private getContacts() {
        return this.contactService.searchForContacts(
            this.searchText.getValue() || '',
            this.limit,
            this.offset
        ).pipe(map((contacts: any) => {
                return contacts;
            })
        );
    }
}
