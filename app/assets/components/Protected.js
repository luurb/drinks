import React from 'react';
import { Navigate } from 'react-router-dom';

const Protected = (props) => {
   return <>{props.user ? props.children : <Navigate to={props.path} replace />}</>;
};

Protected.defaultProps = {
   path: '/drinki'
}
export default Protected;
