import Header from './components/Header';
import Settings from './components/TabSettings';
import '../../css/settings/style.css';
import Notices from './components/Notices';

function App() {
    return (
        <div className='pr-3'>
            <Header />
            <Notices />
            <Settings />
        </div>
    );
}

export default App;