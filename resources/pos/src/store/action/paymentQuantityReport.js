import {setLoading} from './loadingAction';
import {apiBaseURL, productQuantityReportActionType} from '../../constants';
import apiConfig from '../../config/apiConfig';
import {setTotalRecord} from './totalRecordAction';
import requestParam from '../../shared/requestParam';

export const productQuantityReportAction = (id, filter = {}, isLoading = true, setTotalRecords) => async (dispatch) => {
    if (isLoading) {
        dispatch(setLoading(true))
    }
    let url = apiBaseURL.PRODUCT_STOCK_REPORT + `${id !== null ? '/'+id : ''}`;
    if (!_.isEmpty(filter) && (filter.page || filter.pageSize )) {
        url += requestParam(filter, false, false, true);
    }
    await apiConfig.get(url)
        .then((response) => {
            dispatch({type: productQuantityReportActionType.QUANTITY_REPORT, payload: response.data[0].data});
            dispatch(setTotalRecord(response.data[0].manage_stocks.total));
            if (isLoading) {
                dispatch(setLoading(false))
            }
            dispatch(setTotalRecords(response.data[0].manage_stocks.total))
        })
        .catch(({response}) => {
            // dispatch(addToast(
            //     {text: response.data.message, type: toastType.ERROR}));
        });
};
