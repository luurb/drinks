import React from 'react'
import Nav from '../../nav/Nav'
import Login from './Login'

const LoginPage = () => {
  return (
     <>
         <Nav />
         <div className="auth">
            <div className="auth__wrapper">
               <Login />
            </div>
         </div>
     </>
  )
}

export default LoginPage