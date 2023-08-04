import {setLoading} from './loadingAction';
import {apiBaseURL, stockReportActionType, toastType} from '../../constants';
import apiConfig from '../../config/apiConfig';
import {setTotalRecord} from './totalRecordAction';
import {addToast} from './toastAction';
import requestParam from '../../shared/requestParam';

export const stockDetailsPurchaseReturnAction = (id, filter = {}, isLoading = true) => async (dispatch) => {
    if (isLoading) {
        dispatch(setLoading(true))
    }
    let url = apiBaseURL.STOCK_PURCHASE_RETURN_TAB + '?product_id=' + id;
    if (!_.isEmpty(filter) && (filter.page || filter.pageSize || filter.search || filter.order_By || filter.created_at)) {
        url += requestParam(filter);
    }
    await apiConfig.get(url)
        .then((response) => {
            dispatch({type: stockReportActionType.STOCK_DETAILS_PURCHASE_RETURN_TAB, payload: response.data.data});
            dispatch(setTotalRecord(response.data.meta.total));
            if (isLoading) {
                dispatch(setLoading(false))
            }
        })
        .catch(({response}) => {
            dispatch(addToast(
                {text: response.data.message, type: toastType.ERROR}));
        });
};
