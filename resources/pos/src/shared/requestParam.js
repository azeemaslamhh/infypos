import {Filters} from '../constants';

export default (filters = Filters.OBJ, admin, stockReport, isProductQuantity) => {
    let url = '?';
    if (filters.order_By !== '') {
        if (url !== '?') {
            url += '&'
        }
        if (filters.direction === 'asc') {
            url = url + 'sort=' + filters.order_By;
        } else if (filters.direction === 'desc') {
            url = url + 'sort=' + '-' + filters.order_By;
        }
    }
    if (filters.order_By === '') {
        if(isProductQuantity){
        } else {
            if (url !== '?') {
                url += '&'
            }
                url = url + 'sort='  + '-' +  filters.created_at;
        }
        
    }
    if (filters.pageSize > 0) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'page[size]=' + filters.pageSize;
    }
    if (filters.page > 0) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'page[number]=' + filters.page;
    }
    if (filters.search !== '') {
        if (url !== '?') {
            url += '&'
        }
        url += 'filter[search]=' + filters.search;
    }
    if(stockReport) {
        if (filters.search !== '') {
            if (url !== '?') {
                url += '&'
            }
            url += 'search=' + filters.search;
        }
    }

    if (filters.start_date && filters.end_date) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'start_date=' + filters.start_date + '&end_date=' + filters.end_date;
    }

    if (filters.status || filters.payment_status || filters.payment_type) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'status=' + filters.status + '&payment_status=' + filters.payment_status + '&payment_type=' + filters.payment_type;
    }
    
    if (filters.product_unit) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'product_unit=' + filters.product_unit;
    }
    if (filters.base_unit) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'base_unit=' + filters.base_unit;
    }
    if (filters.warehouse_id) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'warehouse_id=' + filters.warehouse_id;
    }
    if (filters.customer_id) {
        if (url !== '?') {
            url += '&'
        }
        url = url + 'customer_id=' + filters.customer_id;
    }
    if (admin) {
        if (url !== '?') {
            url += '&'
        }
        url += 'name=' + filters.adminName;
    }
    return url;
}
