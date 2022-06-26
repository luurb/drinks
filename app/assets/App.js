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
            <Box />
         </main>
      </>
   );
}

export default App;
