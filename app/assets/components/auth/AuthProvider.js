import React, { useEffect, useState } from 'react';
import AuthContext from './AuthContext';

const AuthProvider = ({ children }) => {
   const [user, setUser] = useState(null);
   useEffect(() => {
      window.user && setUser(window.user);
   }, []);

   const value = {
      user: user,
      setUser: setUser,
   };

   return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export default AuthProvider;
