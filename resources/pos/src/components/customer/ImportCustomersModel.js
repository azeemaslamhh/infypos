import React from 'react'
import { useDispatch } from 'react-redux';
import {useNavigate} from 'react-router-dom';
import ImportCustmerFrom from './ImportCustmerFrom';
import { addImportCustomers } from '../../store/action/customerAction';

function ImportCustomersModel(props) {
    const {handleClose, show} = props;
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const addImportData = (formValue) => {
        dispatch(addImportCustomers(formValue, navigate));
    };

    return (
        <>
            <ImportCustmerFrom addImportData={addImportData} handleClose={handleClose} show={show}
                               title={'Import Customer'}/>
        </>
    )
};


export default ImportCustomersModel
