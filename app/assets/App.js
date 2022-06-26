import React from 'react';
import Nav from './components/nav/Nav';
import Info from './components/info/Info';
import Box from './components/drinks-box/Box';

function App() {
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
   );
}

export default App;
