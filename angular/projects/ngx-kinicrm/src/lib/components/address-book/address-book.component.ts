import {Component, OnInit} from '@angular/core';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {AddressService} from '../../services/address.service';
import {debounceTime, map, switchMap} from 'rxjs/operators';

@Component({
  selector: 'kcrm-address-book',
  templateUrl: './address-book.component.html',
  styleUrls: ['./address-book.component.css']
})
export class AddressBookComponent implements OnInit {

    public addresses: any = [];
    public searchText = new BehaviorSubject('');
    public limit = 10;
    public offset = 0;
    public page = 1;
    public endOfResults = false;
    public loading = true;

    private reload = new Subject();

    constructor(private addressService: AddressService) {
    }

    async ngOnInit() {
        merge(this.searchText, this.reload)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.getAddresses()
                )
            ).subscribe((addresses: any) => {
            this.endOfResults = addresses.length < this.limit;
            this.addresses = addresses;
            this.loading = false;
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });
    }

    public increaseOffset() {
        this.page = this.page + 1;
        this.offset = (this.limit * this.page) - this.limit;

    }

    public decreaseOffset() {
        this.page = this.page <= 1 ? 1 : this.page - 1;
        this.offset = (this.limit * this.page) - this.limit;
    }

    public pageSizeChange(value: number) {
        this.limit = value;
    }

    private getAddresses() {
        return this.addressService.searchForAddresses(
            this.searchText.getValue() || '',
            this.limit,
            this.offset
        ).pipe(map((feeds: any) => {
                return feeds;
            })
        );
    }
}
