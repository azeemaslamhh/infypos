import React from 'react'
import {getFormattedMessage} from '../../shared/sharedMethod';
import { useDispatch } from 'react-redux';
import {useNavigate} from 'react-router-dom';
import ImportSupplierForm from './ImportSupplierForm';
import {addImportSupplier} from "../../store/action/supplierAction";

function ImportSuppliersModel(props) {
    const {handleClose, show} = props;
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const addImportData = (formValue) => {
        dispatch(addImportSupplier(formValue, navigate));
    };

    return (
        <>
            <ImportSupplierForm addImportData={addImportData} handleClose={handleClose} show={show}
                               title={'Import Supplier'}/>
        </>
    )
};


export default ImportSuppliersModel
