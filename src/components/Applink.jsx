import { HiPlay } from 'react-icons/hi';
import { StoreIcon } from './StoreIcon';

const Applink = ({ app }) => {
	return (
		<div className={`sual sual-${app.type}`}>
			{console.log(app)}
			<a
				className='sual-figure'
				href={app.url}
				target='_blank'
				rel='noopener nofollow noreferrer'
			>
				<img className='sual-img' src={app.iconUrl} alt={app.title} />
			</a>
			<div className='sual-content'>
				<a
					className='sual-title'
					href={app.url}
					target='_blank'
					rel='noopener nofollow noreferrer'
				>
					{app.title}
				</a>
				<div className='sual-artist'>{app.artist}</div>

				<div className='sual-description'>{app.description}</div>

				<div className='sual-btns'>
					{app.previewUrl && (
						<a
							className='sual-audition sual-btn'
							href={app.previewUrl}
							target='_blank'
							rel='noopener nofollow noreferrer'
						>
							<HiPlay />
							<span className='sual-btn-label'>試聴</span>
						</a>
					)}
					<a
						className='sual-store sual-btn'
						href={app.url}
						target='_blank'
						rel='noopener nofollow noreferrer'
					>
						<StoreIcon type={app.type} />
					</a>
				</div>
			</div>
		</div>
	);
};

export default Applink;
