import React from 'react';
import Nav from '../../nav/Nav';
import SingUp from './SingUp';

const SingUpPage = () => {
   return (
      <>
         <Nav />
         <div className="auth">
            <div className="auth__wrapper">
               <SingUp />
            </div>
         </div>
      </>
   );
};

export default SingUpPage;
