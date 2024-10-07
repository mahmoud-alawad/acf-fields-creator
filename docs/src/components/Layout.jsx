import {Sidebar} from './Sidebar';
import {Content} from './Content';

export const Layout =  function ({ children }) {
  return (
    <div className="flex h-screen">
      <Sidebar />
      <div className="flex-1 p-6 overflow-auto">
        <Content>{children}</Content>
      </div>
    </div>
  );
}
