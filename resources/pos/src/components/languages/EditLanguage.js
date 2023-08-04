import React from 'react';
import {connect} from 'react-redux';
import LanguageForm from './LanguageForm';

const EditLanguage = (props) => {
    const {handleClose, show, language} = props;

    return (
        <>
            {language &&
                <LanguageForm handleClose={handleClose} show={show} singleLanguage={language}
                               title={"Edit Language"}/>
            }
        </>
    )
};

export default connect(null)(EditLanguage);

