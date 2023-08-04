import {setLoading} from './loadingAction';
import {apiBaseURL, stockReportActionType, supplierReportActionType, toastType} from '../../constants';
import apiConfig from '../../config/apiConfig';
import {setTotalRecord} from './totalRecordAction';
import {addToast} from './toastAction';
import requestParam from '../../shared/requestParam';

export const fetchSupplierPurchaseReport = (id, filter = {}, isLoading = true) => async (dispatch) => {
    if (isLoading) {
        dispatch(setLoading(true))
    }
    let url = apiBaseURL.SUPPLIER_PURCHASE_REPORT + "/" + id;
    if (!_.isEmpty(filter) && (filter.page || filter.pageSize || filter.search || filter.order_By || filter.created_at)) {
        url += requestParam(filter);
    }
    await apiConfig.get(url)
        .then((response) => {
            dispatch({type: supplierReportActionType.FETCH_SUPPLIER_PURCHASE_REPORT, payload: response.data.data.data});
            dispatch(setTotalRecord(response.data.data.total));
            if (isLoading) {
                dispatch(setLoading(false))
            }
        })
        .catch(({response}) => {
            dispatch(addToast(
                {text: response.data.message, type: toastType.ERROR}));
        });
};


export const fetchSupplierPurchaseReturnReport = (id, filter = {}, isLoading = true) => async (dispatch) => {
    if (isLoading) {
        dispatch(setLoading(true))
    }
    let url = apiBaseURL.SUPPLIER_PURCHASE_RETURN_REPORT + "/" + id;
    if (!_.isEmpty(filter) && (filter.page || filter.pageSize || filter.search || filter.order_By || filter.created_at)) {
        url += requestParam(filter);
    }
    await apiConfig.get(url)
        .then((response) => {
            dispatch({type: supplierReportActionType.FETCH_SUPPLIER_PURCHASE_RETURN, payload: response.data.data.data});
            dispatch(setTotalRecord(response.data.data.total));
            if (isLoading) {
                dispatch(setLoading(false))
            }
        })
        .catch(({response}) => {
            dispatch(addToast(
                {text: response.data.message, type: toastType.ERROR}));
        });
};
