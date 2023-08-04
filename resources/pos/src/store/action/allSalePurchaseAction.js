import apiConfig from '../../config/apiConfig';
import {apiBaseURL, dashboardActionType, toastType} from '../../constants';
import {addToast} from './toastAction';
import {setLoading} from "./loadingAction";

export const fetchAllSalePurchaseCount = () => async (dispatch) => {
    dispatch(setLoading(true));
    apiConfig.get(apiBaseURL.ALL_SALE_PURCHASE)
        .then((response) => {
            dispatch({type: dashboardActionType.FETCH_ALL_SALE_PURCHASE, payload: response.data.data})
            dispatch(setLoading(false));
        })
        .catch(({response}) => {
            dispatch(addToast(
                {text: response.data.message, type: toastType.ERROR}));
            dispatch(setLoading(false));
        });
}
