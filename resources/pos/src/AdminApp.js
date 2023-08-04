import React from 'react';
import {Route, Navigate, Routes} from 'react-router-dom';
import '../../pos/src/assets/sass/style.react.scss';
import {Tokens} from "./constants";
import {ProtectedRoute} from "./shared/sharedMethod";
import {route} from "./routes";
import TopProgressBar from "./shared/components/loaders/TopProgressBar";

function AdminApp(props) {
    const {config} = props
    const token = localStorage.getItem(Tokens.ADMIN);

    const prepareRoutes = (config) => {
        const permissions = config;
        let filterRoutes = [];
        route.forEach((route) => {
            if (permissions && permissions.indexOf(route.permission) !== -1 || route.permission === '') {
                filterRoutes.push(route)
            }
        });
        return filterRoutes;
    };

    if (config.length === 0) {
        return <TopProgressBar />;
    }

    const routes = config && prepareRoutes(config);

    return (
        <Routes>
            {routes.map((route, index) => {
                return route.ele ? (
                    <Route key={index} exact={true} path={route.path} element={token ? <ProtectedRoute>
                            {route.ele}
                        </ProtectedRoute> :
                        <Navigate replace to={"login"}/>
                    }/>
                ) : (null)
            })}
            <Route path='*' element={<Navigate replace to={"/"}/>}/>
        </Routes>
    )
}

export default AdminApp;
