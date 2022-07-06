import React, { useEffect, useState } from 'react'
import AuthContext from './AuthContext'

const AuthProvider = ({children}) => {
  const [user, setUser] = useState(null);
  useEffect(() => {
    window.user && setUser(window.user)
  }, []);
  return (
     <AuthContext.Provider value={user}>{children}</AuthContext.Provider>
  )
}

export default AuthProvider