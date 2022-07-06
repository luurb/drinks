import React, { useState } from 'react'
import AuthContext from './AuthContext'

const AuthProvider = ({children}) => {
  const [user, setUser] = useState({user: {name: 'test'}});
  return (
     <AuthContext.Provider value={user}>{children}</AuthContext.Provider>
  )
}

export default AuthProvider