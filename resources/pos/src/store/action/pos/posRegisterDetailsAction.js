import apiConfig from '../../../config/apiConfig';
import {apiBaseURL, posProductActionType, toastType} from '../../../constants';
import {addToast} from '../toastAction';

export const fetchTodaySaleOverAllReport = () => async (dispatch) => {
    apiConfig.get(apiBaseURL.TODAY_SALE_OVERALL_REPORT)
        .then((response) => {
            dispatch({type: posProductActionType.FETCH_TODAY_SALE_OVERALL_REPORT, payload: response.data.data})
        })
        .catch(({response}) => {
            dispatch(addToast(
                {text: response.data.message, type: toastType.ERROR}));
        });
};
