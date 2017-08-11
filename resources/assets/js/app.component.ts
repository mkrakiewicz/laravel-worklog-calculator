import { Component } from '@angular/core';

import { ProductService } from './_services/index';

@Component({
    moduleId: module.id.toString(),
    selector: 'app',
    templateUrl: 'app.component.html'
})

export class AppComponent {
    constructor(private productService: ProductService) {
        // add some initial products
        if (productService.getAll().length === 0) {
            productService.save({ name: 'Boardiesies', price: '25.00' }); 
            productService.save({ name: 'Singlett', price: '9.50' });
            productService.save({ name: 'Thongsies (Flip Flops)', price: '12.95' });
        }
    }
}