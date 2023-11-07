import {Injectable} from '@angular/core';

@Injectable({
    providedIn: 'root'
})
export class GravatarService {

    constructor() {
    }

    public async getGravatarURL(email: string, fallback: string = 'mp', size = 512) {
        let hash = '';
        try {
            const textAsBuffer = new TextEncoder().encode((email).toLowerCase());
            const hashBuffer = await window.crypto.subtle.digest('SHA-256', textAsBuffer);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const hash = hashArray
                .map((item) => item.toString(16).padStart(2, '0'))
                .join('');
        } catch (e) {
        }

        return `https://gravatar.com/avatar/${hash}?d=${fallback}&s=${size}`;
    }
}
