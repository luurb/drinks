import React, { useContext } from 'react'
import AuthContext from '../AuthContext'

const Dashboard = () => {
  const user = useContext(AuthContext).user;
  return (
    <div>Hi {user && user.username}</div>
  )
}

export default Dashboard