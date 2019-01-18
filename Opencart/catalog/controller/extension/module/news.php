<?php  
class ControllerExtensionModuleNews extends Controller {
	public function index() {
		$this->language->load('extension/module/news');
		$this->load->model('extension/news');
		
		$filter_data = array(
			'page' => 1,
			'limit' => 3,
			'start' => 0,
		);
	 
		$this->load->model('tool/image');
	 
		$data['heading_title'] = $this->language->get('heading_title');
	 
		$all_news = $this->model_extension_news->getAllNews($filter_data);
	 
		$data['all_news'] = array();
	 
		foreach ($all_news as $news) {
			
			if ($news['image']) {
				$news['image'] = $this->model_tool_image->resize($news['image'], 250, 250);
			} else {
				$news['image'] = '';
			}

			
			$data['all_news'][] = array (
				'image' => $news['image'],
				'title' 		=> html_entity_decode($news['title'], ENT_QUOTES),
				'description' 	=> (utf8_strlen(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))) > 50 ? utf8_substr(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES)), 0, 50) . '...' : strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))),
				'view' 			=> $this->url->link('information/news/news', 'news_id=' . $news['news_id']),
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($news['date_added']))
			);
		}

		
		return $this->load->view('extension/module/news', $data);
	}
}