import React from 'react'
import Info from './info/Info';
import Box from './drinks-box/Box';
import Nav from '../nav/Nav';

const DrinksPage = () => {
  return (
      <>
         <Nav />
         <main className="main__drinks">
            <Info />
            <div className="drinks-box__wrapper">
               <Box />
            </div>
         </main>
      </>
  )
}

export default DrinksPage