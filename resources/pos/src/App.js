import React, {useEffect, useState} from 'react';
import {Route, useLocation, Navigate, Routes} from 'react-router-dom';
import '../../pos/src/assets/sass/style.react.scss';
import {useDispatch, useSelector} from "react-redux";
import {IntlProvider} from "react-intl";
import {settingsKey, Tokens} from "./constants";
import Toasts from "./shared/toast/Toasts";
import {fetchFrontSetting} from "./store/action/frontSettingAction";
import {fetchConfig} from "./store/action/configAction";
import {addRTLSupport} from "./shared/sharedMethod";
import Login from "./components/auth/Login";
import ResetPassword from "./components/auth/ResetPassword";
import ForgotPassword from "./components/auth/ForgotPassword";
import AdminApp from "./AdminApp";
import {getFiles} from "./locales/index"


function App() {
    //do not remove updateLanguage
    const dispatch = useDispatch();
    const location = useLocation();
    const token = localStorage.getItem(Tokens.ADMIN);
    const updatedLanguage = localStorage.getItem(Tokens.UPDATED_LANGUAGE)
    const {selectedLanguage, config, language} = useSelector(state => state)
    const [allLocales, setAllLocales] = useState({})
    const [messages, setMessages] = useState({})

    useEffect(() => {
        const getData = getFiles()
        setAllLocales(getData)
    }, [language[0]?.lang_json_array])


    // updated language hendling
    useEffect(() => {
        if(language[0]?.iso_code === updatedLanguage){
            const updateLanguage = language[0]?.lang_json_array;
            setMessages(updateLanguage)
        } else {
            const updateLanguage = allLocales[updatedLanguage ? updatedLanguage : selectedLanguage];
            if(updateLanguage === undefined){
                const defaultUpdateLanguage = allLocales['en'];
                setMessages(defaultUpdateLanguage)
            } else {
                setMessages(updateLanguage)
            }
        }
    }, [allLocales, language[0]?.lang_json_array])



    useEffect(() => {
        selectCSS();
    }, [location.pathname]);

    useEffect(() => {
        if (token) {
            dispatch(fetchConfig())
            dispatch(fetchFrontSetting());
        }
    },[])

    const selectCSS = () => {
        if (updatedLanguage === 'ar') {
            require('./assets/css/custom.rtl.css');
            require('./assets/css/style.rtl.css');
            require('./assets/css/frontend.rtl.css');
        } else {
            require('./assets/css/custom.css');
            require('./assets/css/style.css');
            require('./assets/css/frontend.css');
        }
    }

    useEffect(() => {
        addRTLSupport(updatedLanguage ? updatedLanguage : selectedLanguage)
    }, [updatedLanguage, selectedLanguage])

    return (
        <div className='d-flex flex-column flex-root'>
            <IntlProvider locale={settingsKey.DEFAULT_LOCALE} messages={messages}>
                <Routes>
                    <Route path='/login' element={<Login/>}/>
                    <Route path='reset-password/:token/:email' element={<ResetPassword/>}/>
                    <Route path='forgot-password' element={<ForgotPassword/>}/>
                    <Route path='app/*' element={<AdminApp config={config}/>}/>
                    <Route path='/' element={<Navigate replace to={token ? "app/dashboard" : "/login"}/>}/>
                    <Route path='*' element={<Navigate replace to={"/"}/>}/>
                </Routes>
                <Toasts language={updatedLanguage ? updatedLanguage : selectedLanguage}/>
            </IntlProvider>
        </div>
    )
}

export default App;

