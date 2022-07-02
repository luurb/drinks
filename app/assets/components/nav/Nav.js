import React from 'react';

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
               <a className="nav__link" href="/drinki">
                  Drinki
               </a>
               <div className="nav__link-box">
                  <a className="nav__link" href="/login">
                     <i className="fa-solid fa-martini-glass"></i>
                     <span className="nav__link">Mój bar</span>
                  </a>
               </div>
            </div>
         </nav>
      </div>
   );
};

export default Nav;
