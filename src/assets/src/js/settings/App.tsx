import Header from './components/Header';
import Settings from './components/TabSettings';
import Notices from './components/Notices';
import '../../css/settings/style.css';

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