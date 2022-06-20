import React from 'react';

const SearchResult = ({name, index}) => {
   return (
      <div
         className="search__result"
         style={{
            background: index % 2 ? 'var(--white)' : 'var(--yellow)',
         }}
      >
         {name}
      </div>
   );
};

export default SearchResult;
