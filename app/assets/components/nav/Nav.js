import React from 'react';
import { Link } from 'react-router-dom';

const Nav = () => {
   return (
      <div className="nav__main">
         <nav className="nav ">
            <a href="/">
               <div className="nav__logo">NieTylkoMohito</div>
            </a>
            <input type="checkbox" id="nav" />
            <label htmlFor="nav">
               <i className="fa-solid fa-bars"></i>
            </label>
            <div className="nav__right">
               <Link className="nav__link" to="/drinki">
                  Drinki
               </Link>
               <div className="nav__link-box">
                  <Link className="nav__link" to="/login">
                     <span>Mój bar</span>
                     <i className="fa-solid fa-martini-glass"></i>
                  </Link>
               </div>
            </div>
         </nav>
      </div>
   );
};

export default Nav;
